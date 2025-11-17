document.addEventListener('alpine:init', () => {
    Alpine.data('filterBar', ({ fields = [], filters = [], table = '#applicationsTable' } = {}) => ({
        search: '',
        filters: filters,
        fields: fields,
        table: table,

        // multi checkboxes status (flat list values, seperti "status_pengajuan:approved")
        multi: [],

        // values for each filter key (text/select/date-range)
        values: {},

        init() {
            // init values
            this.filters.forEach(f => {
                if (f.type === 'date-range') {
                    this.values[f.key] = { preset: '', from: '', to: '' };
                } else {
                    this.values[f.key] = '';
                }
            });

            // initial apply (optional)
            this.apply();
        },

        // reset all
        reset() {
            this.search = '';
            this.multi = [];
            this.filters.forEach(f => {
                if (f.type === 'date-range') {
                    this.values[f.key] = { preset: '', from: '', to: '' };
                } else this.values[f.key] = '';
            });
            this.apply();
        },

        // helper parse date when dataset has Y-m-d or d M Y fallback
        parseRowDate(v) {
            if (!v) return null;
            // prefer YYYY-MM-DD
            if (/^\d{4}-\d{2}-\d{2}$/.test(v)) return new Date(v + 'T00:00:00');
            // fallback "d M Y" like "21 Jan 2025"
            const months = { jan:0,feb:1,mar:2,apr:3,mei:4,jun:5,jul:6,agu:7,sep:8,okt:9,nov:10,des:11 };
            const parts = v.toLowerCase().split(' ');
            if (parts.length >= 3) return new Date(parts[2], months[parts[1]] ?? 0, parts[0]);
            return new Date(v);
        },

        // main filter logic
        apply() {
            const rows = document.querySelectorAll(`${this.table} tbody tr`);
            const keywords = this.search.toLowerCase().split(' ').filter(Boolean);

            rows.forEach(row => {
                let visible = true;

                // 1) search across configured fields
                if (keywords.length > 0 && this.fields.length) {
                    let matchSearch = false;
                    for (const key of this.fields) {
                        const val = (row.dataset[key] || '').toLowerCase();
                        if (keywords.every(kw => val.includes(kw))) {
                            matchSearch = true;
                            break;
                        }
                    }
                    if (!matchSearch) visible = false;
                }

                // 2) evaluate each filter
                for (const f of this.filters) {
                    if (!visible) break;

                    const rowVal = (row.dataset[f.key] || '').toLowerCase();

                    if (f.type === 'text') {
                        const v = (this.values[f.key] || '').toLowerCase();
                        if (v && !rowVal.includes(v)) visible = false;
                    }

                    if (f.type === 'select') {
                        const v = (this.values[f.key] || '').toLowerCase();
                        if (v && rowVal !== v) visible = false;
                    }

                    if (f.type === 'checkbox-list') {
                        // check if any corresponding multi checkbox values exist for this key
                        const selected = this.multi
                            .filter(x => x.startsWith(f.key + ':'))
                            .map(x => x.split(':')[1]);

                        if (selected.length > 0 && !selected.includes(rowVal)) visible = false;
                    }

                    if (f.type === 'date-range') {
                        const cfg = this.values[f.key];
                        if (cfg && cfg.preset) {
                            const created = this.parseRowDate(row.dataset[f.key]); // expects dataset uses same key
                            if (!created) { visible = false; continue; }

                            const today = new Date();
                            if (cfg.preset === 'today') {
                                if (created.toDateString() !== today.toDateString()) visible = false;
                            }
                            if (cfg.preset === '7') {
                                const limit = new Date(); limit.setDate(today.getDate() - 7);
                                if (created < limit) visible = false;
                            }
                            if (cfg.preset === '30') {
                                const limit = new Date(); limit.setDate(today.getDate() - 30);
                                if (created < limit) visible = false;
                            }
                            if (cfg.preset === 'custom') {
                                if (cfg.from) {
                                    const s = new Date(cfg.from + "T00:00:00");
                                    if (created < s) visible = false;
                                }
                                if (cfg.to) {
                                    const e = new Date(cfg.to + "T23:59:59");
                                    if (created > e) visible = false;
                                }
                            }
                        }
                    }
                }

                row.style.display = visible ? '' : 'none';
            });

            let visibleCount = 0;

            rows.forEach(r => {
                if (r.id === 'noDataMessage') return;

                if (r.style.display !== 'none') {
                    visibleCount++;
                }
            });

            const msgRow = document.querySelector('#noDataMessage');

            if (msgRow) {
                msgRow.style.display = ''; // restore as table-row

                if (visibleCount > 0) {
                    msgRow.classList.add('hidden');
                } else {
                    msgRow.classList.remove('hidden');
                }
            }


        }
    }));
});

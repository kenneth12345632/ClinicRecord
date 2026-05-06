@once('material-calendar-flatpickr-scripts-bundle')
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script>
(function () {
    function formatMaterialHeadline(date) {
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
    }

    function ensureMaterialFpBanner(fp) {
        var root = fp.calendarContainer;
        if (!root || root.querySelector('.fp-minv-material-banner')) {
            return;
        }
        var banner = document.createElement('div');
        banner.className = 'fp-minv-material-banner';
        banner.innerHTML =
            '<div class="fp-minv-top-row">' +
            '<span class="fp-minv-select-label">Select date</span>' +
            '<span class="fp-minv-pencil" aria-hidden="true">' +
            '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
            '</span></div>' +
            '<div class="fp-minv-headline" aria-live="polite"></div>';
        root.insertBefore(banner, root.firstChild);
    }

    function updateMaterialFpHeadline(fp) {
        var root = fp.calendarContainer;
        if (!root) {
            return;
        }
        var line = root.querySelector('.fp-minv-headline');
        if (!line) {
            return;
        }
        var d = fp.selectedDates[0];
        line.textContent = d ? formatMaterialHeadline(d) : '';
    }

    function bindMaterialFpSurface(fp) {
        fp.calendarContainer.classList.add('fp-material-calendar');
        ensureMaterialFpBanner(fp);
        updateMaterialFpHeadline(fp);
    }

    function parseAttrDate(attr, edge) {
        if (!attr || String(attr).trim() === '') {
            return undefined;
        }
        if (String(attr).toLowerCase() === 'today') {
            var t = new Date();
            if (edge === 'min') {
                t.setHours(0, 0, 0, 0);
            } else if (edge === 'max') {
                t.setHours(23, 59, 59, 999);
            }
            return t;
        }
        var s = String(attr).trim();
        if (/^\d{4}-\d{2}-\d{2}$/.test(s)) {
            return s;
        }
        return s;
    }

    function minCalendarYearFromMinDate(minDate) {
        if (!minDate) {
            return null;
        }
        var d =
            minDate instanceof Date ? minDate : new Date(minDate);
        return isNaN(d.getTime()) ? null : d.getFullYear();
    }

    function medicineInventoryYearBounds(fp) {
        var yMin = minCalendarYearFromMinDate(fp.config.minDate);
        if (yMin == null) {
            return null;
        }
        return { yMin: yMin, yMax: yMin + 50 };
    }

    function clampMedicineYear(y, yMin, yMax) {
        if (typeof y !== 'number' || isNaN(y)) {
            return null;
        }
        return Math.min(yMax, Math.max(yMin, y));
    }

    /* Inventory: typed year input + compact list (5 visible rows via size="5") */
    function installMedicineInventoryYearCombo(fp) {
        var bounds = medicineInventoryYearBounds(fp);
        if (!bounds || !fp.calendarContainer) {
            return;
        }
        var yMin = bounds.yMin;
        var yMax = bounds.yMax;
        requestAnimationFrame(function () {
            var cal = fp.calendarContainer;
            if (!cal) {
                return;
            }
            var wrap = cal.querySelector('.flatpickr-current-month .numInputWrapper');
            if (!wrap) {
                return;
            }

            wrap.querySelectorAll('select.fp-inv-year-dropdown').forEach(function (n) {
                n.remove();
            });

            var stack = wrap.querySelector('.fp-inv-year-stack');
            if (!stack) {
                stack = document.createElement('div');
                stack.className = 'fp-inv-year-stack';
                wrap.insertBefore(stack, wrap.firstChild);
            }

            var inp = stack.querySelector('input.fp-inv-year-direct');
            var sel = stack.querySelector('select.fp-inv-year-list');
            if (!inp) {
                inp = document.createElement('input');
                inp.type = 'number';
                inp.className = 'fp-inv-year-direct';
                inp.setAttribute('inputmode', 'numeric');
                inp.setAttribute('aria-label', 'Year (type)');
                stack.appendChild(inp);
            }
            if (!sel) {
                sel = document.createElement('select');
                sel.className = 'fp-inv-year-list';
                sel.setAttribute('size', '5');
                sel.setAttribute('aria-label', 'Year (list)');
                stack.appendChild(sel);
            }

            inp.setAttribute('min', String(yMin));
            inp.setAttribute('max', String(yMax));

            sel.innerHTML = '';
            for (var yi = yMin; yi <= yMax; yi++) {
                var opt = document.createElement('option');
                opt.value = String(yi);
                opt.textContent = String(yi);
                sel.appendChild(opt);
            }

            var cy = fp.currentYear;
            cy = clampMedicineYear(cy, yMin, yMax) || yMin;
            inp.value = String(cy);
            sel.value = String(cy);

            if (!stack.dataset.fpInvYearListeners) {
                stack.dataset.fpInvYearListeners = '1';

                inp.addEventListener('change', function () {
                    var b = medicineInventoryYearBounds(fp);
                    if (!b) {
                        return;
                    }
                    var vmin = b.yMin;
                    var vmax = b.yMax;
                    var iEl = stack.querySelector('input.fp-inv-year-direct');
                    var sEl = stack.querySelector('select.fp-inv-year-list');
                    if (!iEl || !sEl) {
                        return;
                    }
                    var v = parseInt(String(iEl.value), 10);
                    var c = clampMedicineYear(v, vmin, vmax);
                    if (c == null) {
                        c = clampMedicineYear(fp.currentYear, vmin, vmax) || vmin;
                    }
                    iEl.value = String(c);
                    sEl.value = String(c);
                    if (fp.changeYear) {
                        fp.changeYear(c);
                    }
                });
                inp.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        stack.querySelector('input.fp-inv-year-direct').dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });

                sel.addEventListener('change', function () {
                    var b = medicineInventoryYearBounds(fp);
                    if (!b) {
                        return;
                    }
                    var vmin = b.yMin;
                    var vmax = b.yMax;
                    var iEl = stack.querySelector('input.fp-inv-year-direct');
                    var sEl = stack.querySelector('select.fp-inv-year-list');
                    if (!iEl || !sEl) {
                        return;
                    }
                    var v = parseInt(String(sEl.value), 10);
                    var c = clampMedicineYear(v, vmin, vmax);
                    if (c == null) {
                        return;
                    }
                    iEl.value = String(c);
                    if (fp.changeYear) {
                        fp.changeYear(c);
                    }
                });
            }

            var numIn = wrap.querySelector('input.numInput.cur-year');
            if (numIn) {
                numIn.classList.add('fp-inv-year-native-hide');
                numIn.setAttribute('tabindex', '-1');
            }
        });
    }

    function attachMaterialHooks(opts, extras) {
        extras = extras || {};
        var origReady = opts.onReady;
        var origOpen = opts.onOpen;
        var origChange = opts.onChange;
        var origMonthChange = opts.onMonthChange;
        var origYearChange = opts.onYearChange;
        opts.onReady = function (a, b, fp) {
            bindMaterialFpSurface(fp);
            if (extras.medicineInventoryPicker) {
                fp.calendarContainer.classList.add('fp-medicine-inventory-picker');
                installMedicineInventoryYearCombo(fp);
            }
            if (typeof origReady === 'function') {
                origReady.call(this, a, b, fp);
            }
        };
        opts.onOpen = function (a, b, fp) {
            updateMaterialFpHeadline(fp);
            if (extras.medicineInventoryPicker) {
                installMedicineInventoryYearCombo(fp);
            }
            if (typeof origOpen === 'function') {
                origOpen.call(this, a, b, fp);
            }
        };
        opts.onMonthChange = function (a, b, fp) {
            if (extras.medicineInventoryPicker) {
                installMedicineInventoryYearCombo(fp);
            }
            if (typeof origMonthChange === 'function') {
                origMonthChange.call(this, a, b, fp);
            }
        };
        opts.onYearChange = function (a, b, fp) {
            if (extras.medicineInventoryPicker) {
                installMedicineInventoryYearCombo(fp);
            }
            if (typeof origYearChange === 'function') {
                origYearChange.call(this, a, b, fp);
            }
        };
        opts.onChange = function (a, b, fp) {
            updateMaterialFpHeadline(fp);
            if (typeof origChange === 'function') {
                origChange.call(this, a, b, fp);
            }
        };
    }

    /* —— Medicine expiry / arrival (min = today server rule) —— */
    function mountMedicineExpiryPickers() {
        if (typeof flatpickr === 'undefined') {
            return;
        }
        var min = new Date();
        min.setHours(0, 0, 0, 0);
        document.querySelectorAll('input[data-medicine-expiry], input[data-medicine-arrival]').forEach(function (el) {
            if (el._flatpickr) {
                return;
            }
            var editDates = el.getAttribute('data-inventory-edit-dates') === 'true';
            var def = el.getAttribute('data-default');
            if (editDates) {
                if (!def || String(def).trim() === '') {
                    def = null;
                }
            } else {
                if (def) {
                    var parsedEarly = new Date(def + 'T12:00:00');
                    if (parsedEarly < min) {
                        def = null;
                    }
                } else {
                    def = null;
                }
            }
            var altClass = el.getAttribute('data-alt-class')
                || 'w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 outline-none text-sm font-semibold text-gray-900 transition';
            var o = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                altInputClass: altClass,
                defaultDate: def || undefined,
                disableMobile: true,
                allowInput: false,
                closeOnSelect: true
            };
            if (!editDates) {
                o.minDate = min;
            }
            attachMaterialHooks(o, editDates ? null : { medicineInventoryPicker: true });
            flatpickr(el, o);
        });
    }

    /* —— Birthday (#birthday text input) —— */
    function mountBirthdayFlatpick() {
        if (typeof flatpickr === 'undefined') {
            return;
        }
        var el = document.getElementById('birthday');
        if (!el || el.dataset.birthdayPickerReadonly === '1' || el.dataset.birthdayMaterialPickerMounted === '1') {
            return;
        }
        el.dataset.birthdayMaterialPickerMounted = '1';

        var def = el.getAttribute('data-default');
        var defaultDt = def && String(def).trim() !== '' ? String(def).trim() : undefined;
        var altClassAttr = el.getAttribute('data-alt-class');
        var o = {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            altInputClass: altClassAttr ? altClassAttr : 'w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm outline-none transition font-semibold text-gray-900',
            defaultDate: defaultDt || undefined,
            disableMobile: false,
            allowInput: false,
            closeOnSelect: true,
            onChange: function (_, __, inst) {
                if (typeof window.calculateAge === 'function') {
                    window.calculateAge();
                }
                updateMaterialFpHeadline(inst);
            }
        };
        attachMaterialHooks(o, null);
        flatpickr(el, o);

        if (typeof window.calculateAge === 'function') {
            window.calculateAge();
        }
    }

    /* —— Any input[data-material-calendar] —— */
    function mountMarkedMaterialCalendars() {
        if (typeof flatpickr === 'undefined') {
            return;
        }
        document.querySelectorAll('input[data-material-calendar]').forEach(function (el) {
            if (el._flatpickr) {
                return;
            }
            if (el.readOnly || el.hasAttribute('readonly') || el.disabled) {
                return;
            }
            el.dataset.materialCalendarMounted = '1';
            var def = el.getAttribute('data-default');
            var altClassRaw = el.getAttribute('data-alt-class');
            var altClass =
                altClassRaw && altClassRaw.trim() !== ''
                    ? altClassRaw
                    : 'w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none';
            var altFmt = el.getAttribute('data-fp-alt-format') || 'd/m/Y';
            var allowMobileNative = el.getAttribute('data-fp-native-mobile') === 'true';
            var minA = el.getAttribute('data-fp-min');
            var maxA = el.getAttribute('data-fp-max');
            var closeOnSel = el.getAttribute('data-fp-close-on-select');
            var closeOnSelect = closeOnSel !== 'false';
            var o = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: altFmt,
                altInputClass: altClass,
                defaultDate: def && def.trim() !== '' ? def.trim() : undefined,
                disableMobile: !allowMobileNative,
                allowInput: false,
                closeOnSelect: closeOnSelect
            };
            if (minA) {
                var pm = parseAttrDate(minA, 'min');
                if (pm) {
                    o.minDate = pm;
                }
            }
            if (maxA) {
                var px = parseAttrDate(maxA, 'max');
                if (px) {
                    o.maxDate = px;
                }
            }
            attachMaterialHooks(o, null);
            flatpickr(el, o);
        });
    }

    function bootMaterialFlatpickr() {
        mountMedicineExpiryPickers();
        mountBirthdayFlatpick();
        mountMarkedMaterialCalendars();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootMaterialFlatpickr);
    } else {
        bootMaterialFlatpickr();
    }
})();
</script>
@endonce

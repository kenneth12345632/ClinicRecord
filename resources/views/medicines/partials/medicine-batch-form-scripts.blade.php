<script>
    const genericOptions = [
        { header: 'A' }, 'Acetaminophen', 'Acyclovir', 'Albuterol', 'Allopurinol', 'Amlodipine', 'Amoxicillin', 'Amitriptyline', 'Aspirin', 'Atorvastatin', 'Azithromycin',
        { header: 'B' }, 'Baclofen', 'Beclomethasone', 'Benzonatate', 'Bisoprolol', 'Budesonide',
        { header: 'C' }, 'Captopril', 'Carbamazepine', 'Cefalexin', 'Ceftriaxone', 'Cetirizine', 'Chlorpheniramine', 'Ciprofloxacin', 'Clarithromycin', 'Clopidogrel',
        { header: 'D' }, 'Diazepam', 'Diclofenac', 'Digoxin', 'Diphenhydramine', 'Doxycycline',
        { header: 'E' }, 'Enalapril', 'Erythromycin', 'Escitalopram',
        { header: 'F' }, 'Famotidine', 'Furosemide', 'Fluoxetine',
        { header: 'G' }, 'Gabapentin', 'Gliclazide',
        { header: 'H' }, 'Hydrochlorothiazide', 'Hydrocortisone',
        { header: 'I' }, 'Ibuprofen', 'Insulin', 'Isoniazid',
        { header: 'K' }, 'Ketoconazole',
        { header: 'L' }, 'Lansoprazole', 'Levothyroxine', 'Lisinopril', 'Loratadine', 'Losartan',
        { header: 'M' }, 'Metformin', 'Metoprolol', 'Metronidazole', 'Montelukast',
        { header: 'N' }, 'Naproxen', 'Nifedipine',
        { header: 'O' }, 'Omeprazole', 'Ondansetron',
        { header: 'P' }, 'Pantoprazole', 'Paracetamol', 'Penicillin', 'Prednisone',
        { header: 'R' }, 'Ranitidine', 'Rifampicin',
        { header: 'S' }, 'Salbutamol', 'Sertraline', 'Simvastatin', 'Spironolactone',
        { header: 'T' }, 'Tramadol',
        { header: 'V' }, 'Valsartan',
        { header: 'W' }, 'Warfarin',
        { header: 'Z' }, 'Zinc sulfate', 'Zolpidem',
    ];
    const brandOptions = [
        { header: 'A' }, 'Abilify', 'Actifed', 'Adalat', 'Advil', 'Aerius', 'Aldactone', 'Alaxan', 'Allegra', 'Allerta', 'Ambien', 'Ambrolex', 'Amoxil', 'Augmentin', 'Aspirin Protect', 'Atarax', 'Atenolol (various brands)', 'Ativan', 'Atozet', 'Avamys',
        { header: 'B' }, 'Bactidol', 'Bactrim', 'Benadryl', 'Berocca', 'Betadine', 'Bioflu', 'Biogesic', 'Bonamine', 'Bonviva', 'Brufen', 'Buscopan', 'Bystolic',
        { header: 'C' }, 'Calpol', 'Campral', 'Capoten', 'Cardizem', 'Cataflam', 'Celebrex', 'Ceelin', 'Centrum', 'Cipro', 'Claritin', 'Clarinase', 'Clavulin', 'Co-Amoxiclav (various brands)', 'Cozaar', 'Crestor', 'Cymbalta',
        { header: 'D' }, 'Daktarin', 'Decolgen', 'Deltasone', 'Dettol', 'Diovan', 'Diphereline', 'Dulcolax', 'Duspatalin',
        { header: 'E' }, 'Elica', 'Elavil', 'Elevit', 'Emanera', 'Emeset', 'Enervon', 'Erythrocin', 'Eskinol', 'Exforge', 'Exelon',
        { header: 'F' }, 'Flagyl', 'Flomax', 'Forxiga', 'Fortum', 'Fucidin', 'Furosemide (various brands)',
        { header: 'G' }, 'Gaviscon', 'Glucophage', 'Gluta-C', 'Glycomet', 'Gravol',
        { header: 'H' }, 'Hemarate', 'Hemovit', 'Humalog', 'Humulin', 'Hydrite',
        { header: 'I' }, 'Imodium', 'Imuran', 'Inderal', 'Insulatard', 'Irbesartan (various brands)', 'Isoptin',
        { header: 'J' }, 'Jardiance', 'Josacine',
        { header: 'K' }, 'Kalium Durule', 'Keflex', 'Ketosteril', 'Klaricid', 'Kremil-S',
        { header: 'L' }, 'Lacosteine', 'Lactacyd', 'Lamisil', 'Lasix', 'Leflox', 'Lescol', 'Levoxyl', 'Lexapro', 'Lipitor', 'Lisinopril (various brands)', 'Loperamide (various brands)', 'Losec', 'Lovenox',
        { header: 'M' }, 'Maalox', 'Macrobid', 'Medicol', 'Medrol', 'Meloxicam (various brands)', 'Meronem', 'Micardis', 'Microgynon', 'Motilium', 'Motrin', 'Mucosolvan', 'Myonal',
        { header: 'N' }, 'Natrilix', 'Neozep', 'Neurontin', 'Nexium', 'Nifedipine (various brands)', 'Nizoral', 'Norflex', 'Norvasc', 'Novolin', 'NovoRapid',
        { header: 'O' }, 'Olmetec', 'Omnicef', 'Omron (medical products brand)', 'Onbrez', 'Oracort', 'Oral-B (medical/dental brand)',
        { header: 'P' }, 'Panadol', 'Pantoloc', 'Pariet', 'Pen-Vee K', 'Pharex B-Complex', 'Plasil', 'Plavix', 'Ponstan', 'Pred Forte', 'Protonix', 'Pulmicort',
        { header: 'Q' }, 'Questran', 'Quibron',
        { header: 'R' }, 'Ranitac', 'Relenza', 'Renitec', 'Rhinathiol', 'Rivotril', 'Rocephin', 'Rogin-E', 'Roxithromycin (various brands)',
        { header: 'S' }, 'Salonpas', 'Sandostatin', 'Sandoz (pharma brand)', 'Serc', 'Seretide', 'Seroquel', 'Sinutab', 'Solmux', 'Spiriva', 'Stresstabs', 'Symbicort', 'Synthroid',
        { header: 'T' }, 'Tamiflu', 'Tegretol', 'Tempra', 'Tenormin', 'Terbinafine (various brands)', 'Tetanus Toxoid (various brands)', 'Tobrex', 'Tramal', 'Trihydral', 'Tuseran',
        { header: 'U' }, 'Ultram', 'Unilab (pharma brand)', 'Ursinol',
        { header: 'V' }, 'Valium', 'Vastarel', 'Ventolin', 'Vermox', 'Vibramycin', 'Vidisic', 'Voren',
        { header: 'W' }, 'Warfarin (various brands)',
        { header: 'X' }, 'Xalatan', 'Xanax', 'Xigduo', 'Xyzal',
        { header: 'Y' }, 'Yasmin', 'Yaz',
        { header: 'Z' }, 'Zantac', 'Zestril', 'Zinnat', 'Zithromax', 'Zocor', 'Zoloft', 'Zovirax',
    ];

    const medicineTypes = [
        'Tablet',
        'Capsule',
        'Syrup',
        'Suspension',
        'Drops',
        'Inhaler',
        'Cream',
        'Ointment',
        'Eye Drops',
        'Nebule',
    ];
    const dbGenericOptions = @json(($dbGenericOptions ?? collect())->values());
    const dbBrandOptions = @json(($dbBrandOptions ?? collect())->values());
    const dbTypeOptions = @json(($dbTypeOptions ?? collect())->values());

    const typeWrap = document.getElementById('medicineTypeDropdownWrap');
    const typeSearchInput = document.getElementById('medicine_type_search');
    const typeHiddenInput = document.getElementById('medicine_type');
    const typeDropdown = document.getElementById('medicine_type_dropdown');
    const genericAddNewToggle = document.getElementById('generic_add_new');
    const brandAddNewToggle = document.getElementById('brand_add_new');
    const typeAddNewToggle = document.getElementById('type_add_new');
    const genericWrap = document.getElementById('genericDropdownWrap');
    const genericInput = document.getElementById('generic_name');
    const genericDropdown = document.getElementById('generic_dropdown');
    const brandWrap = document.getElementById('brandDropdownWrap');
    const brandInput = document.getElementById('brand_name');
    const brandDropdown = document.getElementById('brand_dropdown');

    function stringOptions(options) {
        return options.filter(item => typeof item === 'string').map(item => item.toLowerCase());
    }

    function existsInOptions(value, options) {
        return stringOptions(options).includes((value || '').trim().toLowerCase());
    }

    dbGenericOptions.forEach(item => {
        if (!existsInOptions(item, genericOptions)) {
            genericOptions.push(item);
        }
    });
    dbBrandOptions.forEach(item => {
        if (!existsInOptions(item, brandOptions)) {
            brandOptions.push(item);
        }
    });
    dbTypeOptions.forEach(item => {
        if (!existsInOptions(item, medicineTypes)) {
            medicineTypes.push(item);
        }
    });

    function renderAlphabeticalDropdown(dropdownEl, options, inputEl, addNewToggle, filter = '', emptyText = 'No matching item found.') {
        const query = filter.trim().toLowerCase();
        dropdownEl.innerHTML = '';

        if (query) {
            const matches = options.filter(item => typeof item === 'string' && item.toLowerCase().includes(query));
            if (matches.length === 0) {
                const empty = document.createElement('button');
                empty.type = 'button';
                empty.className = 'w-full text-left px-4 py-2.5 text-sm text-blue-600 font-semibold hover:bg-blue-50';
                empty.textContent = `+ Add "${inputEl.value.trim()}" as new`;
                empty.addEventListener('click', function () {
                    addNewToggle.checked = true;
                    dropdownEl.classList.add('hidden');
                });
                dropdownEl.appendChild(empty);
                return;
            }

            matches.forEach(name => {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50';
                option.textContent = name;
                option.addEventListener('click', function () {
                    inputEl.value = name;
                    addNewToggle.checked = false;
                    dropdownEl.classList.add('hidden');
                });
                dropdownEl.appendChild(option);
            });

            return;
        }

        options.forEach(item => {
            if (typeof item === 'string') {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50';
                option.textContent = item;
                option.addEventListener('click', function () {
                    inputEl.value = item;
                    addNewToggle.checked = false;
                    dropdownEl.classList.add('hidden');
                });
                dropdownEl.appendChild(option);
                return;
            }

            const header = document.createElement('div');
            header.className = 'px-4 py-2 text-[11px] font-black text-gray-400 uppercase tracking-wider bg-gray-50 sticky top-0';
            header.textContent = item.header;
            dropdownEl.appendChild(header);
        });
    }

    function renderGenericOptions(filter = '') {
        renderAlphabeticalDropdown(
            genericDropdown,
            genericOptions,
            genericInput,
            genericAddNewToggle,
            filter,
            'No matching generic name found.'
        );
    }
    function renderBrandOptions(filter = '') {
        renderAlphabeticalDropdown(
            brandDropdown,
            brandOptions,
            brandInput,
            brandAddNewToggle,
            filter,
            'No matching brand name found.'
        );
    }

    function renderTypeOptions(filter = '') {
        const query = filter.trim().toLowerCase();
        const filteredTypes = medicineTypes.filter(type => type.toLowerCase().includes(query));
        typeDropdown.innerHTML = '';

        if (filteredTypes.length === 0) {
            const empty = document.createElement('button');
            empty.type = 'button';
            empty.className = 'w-full text-left px-4 py-2.5 text-sm text-blue-600 font-semibold hover:bg-blue-50';
            empty.textContent = `+ Add "${typeSearchInput.value.trim()}" as new`;
            empty.addEventListener('click', function () {
                typeHiddenInput.value = typeSearchInput.value.trim();
                typeAddNewToggle.checked = true;
                typeDropdown.classList.add('hidden');
            });
            typeDropdown.appendChild(empty);
            return;
        }

        filteredTypes.forEach(type => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50';
            option.textContent = type;
            option.addEventListener('click', function () {
                typeSearchInput.value = type;
                typeHiddenInput.value = type;
                typeAddNewToggle.checked = false;
                typeDropdown.classList.add('hidden');
            });
            typeDropdown.appendChild(option);
        });
    }

    typeSearchInput.addEventListener('focus', function () {
        renderTypeOptions(typeSearchInput.value);
        typeDropdown.classList.remove('hidden');
    });

    typeSearchInput.addEventListener('input', function () {
        const typed = typeSearchInput.value.trim();
        typeHiddenInput.value = typed;
        if (existsInOptions(typed, medicineTypes)) {
            typeAddNewToggle.checked = false;
        }
        renderTypeOptions(typed);
        typeDropdown.classList.remove('hidden');
    });

    genericInput.addEventListener('focus', function () {
        renderGenericOptions(genericInput.value);
        genericDropdown.classList.remove('hidden');
    });

    genericInput.addEventListener('input', function () {
        if (existsInOptions(genericInput.value, genericOptions)) {
            genericAddNewToggle.checked = false;
        }
        renderGenericOptions(genericInput.value);
        genericDropdown.classList.remove('hidden');
    });
    brandInput.addEventListener('focus', function () {
        renderBrandOptions(brandInput.value);
        brandDropdown.classList.remove('hidden');
    });

    brandInput.addEventListener('input', function () {
        if (existsInOptions(brandInput.value, brandOptions)) {
            brandAddNewToggle.checked = false;
        }
        renderBrandOptions(brandInput.value);
        brandDropdown.classList.remove('hidden');
    });

    document.addEventListener('click', function (event) {
        if (!typeWrap.contains(event.target)) {
            typeDropdown.classList.add('hidden');
        }
        if (!genericWrap.contains(event.target)) {
            genericDropdown.classList.add('hidden');
        }
        if (!brandWrap.contains(event.target)) {
            brandDropdown.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (typeSearchInput.value.trim()) {
            typeHiddenInput.value = typeSearchInput.value.trim();
        }
        if (genericInput.value.trim() && !existsInOptions(genericInput.value, genericOptions)) {
            genericAddNewToggle.checked = true;
        }
        if (brandInput.value.trim() && !existsInOptions(brandInput.value, brandOptions)) {
            brandAddNewToggle.checked = true;
        }
        if (typeHiddenInput.value.trim() && !existsInOptions(typeHiddenInput.value, medicineTypes)) {
            typeSearchInput.value = typeHiddenInput.value;
            typeAddNewToggle.checked = true;
        }
    });

    document.getElementById('medicineForm').addEventListener('submit', function(e) {
        const brand = document.getElementById('brand_name').value.trim();
        const generic = document.getElementById('generic_name').value.trim();
        const dosageValue = document.getElementById('dosage_value').value.trim();
        const dosageUnit = document.getElementById('dosage_unit').value.trim();
        const type = document.getElementById('medicine_type').value.trim();

        const genericIsExisting = existsInOptions(generic, genericOptions);
        const brandIsExisting = existsInOptions(brand, brandOptions);
        const typeIsExisting = existsInOptions(type, medicineTypes);

        if (!genericIsExisting && !genericAddNewToggle.checked) {
            e.preventDefault();
            alert('Generic Name is new. Check "Add New" beside Generic Name to continue.');
            return;
        }

        if (!brandIsExisting && !brandAddNewToggle.checked) {
            e.preventDefault();
            alert('Brand Name is new. Check "Add New" beside Brand Name to continue.');
            return;
        }

        if (!typeIsExisting && !typeAddNewToggle.checked) {
            e.preventDefault();
            alert('Type is new. Check "Add New" beside Type to continue.');
            return;
        }

        document.getElementById('combined_name').value = `${brand} (${generic}) ${dosageValue}${dosageUnit} ${type}`.trim();
    });
</script>

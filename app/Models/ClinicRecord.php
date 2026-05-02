<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClinicRecord extends Model
{
    use HasFactory;
    private const DOCTOR_PLACEHOLDER_DIAGNOSIS = 'waiting_for_doctor/nurse';

    protected $casts = [
        'consultation_date' => 'date',
        'birthday' => 'date',
        'published_to_registry_at' => 'datetime',
    ];

protected $fillable = [
    'first_name', 
    'middle_name', 
    'last_name', 
    'birthday', 
    'age', 
    'gender', 
    'civil_status', 
    'contact_number',
    'address_purok', 
    'consultation_date',
    'temp',   // Add this
    'bp',     // Add this
    'pr',     // Add this
    'rr',     // Add this
    'weight', // Add this
    'height', // Add this
    'bmi',    // Add this
    'subjective', 
    'objective', 
    'diagnosis',
    'condition_update',
    'follow_up_recommendation',
    'medicines_given',
    'laboratory_image_path',
    'consulted_by',
    'doctor_consulted_by',
    'published_to_registry_at',
];
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'clinic_record_medicine')
                    ->withPivot(['id', 'quantity', 'dispensed_at'])
                    ->withTimestamps();
    }

    /**
     * Visits BHW must clear before they appear on Clinic Records:
     * — prescribed lines not yet dispensed, or
     * — no medicines but visit not yet published to registry.
     *
     * Only consultations saved by a **doctor** (doctor_consulted_by starts with "Dr.") appear here.
     * Nurse-only visits stay out until a doctor saves a consultation for that encounter.
     */
    public function scopeAwaitingMedicineDispensing(Builder $query): Builder
    {
        return $query
            ->whereNotNull('diagnosis')
            ->where('diagnosis', '!=', self::DOCTOR_PLACEHOLDER_DIAGNOSIS)
            ->whereNotNull('doctor_consulted_by')
            ->whereRaw('LOWER(TRIM(doctor_consulted_by)) LIKE ?', ['dr.%'])
            ->whereNull('published_to_registry_at')
            ->where(function ($outer) {
                $outer->whereHas('medicines', function ($q) {
                    $q->whereNull('clinic_record_medicine.dispensed_at');
                })->orWhereDoesntHave('medicines');
            });
    }

    public function laboratoryFiles(): HasMany
    {
        return $this->hasMany(ClinicRecordFile::class, 'clinic_record_id');
    }

    public function getWorkflowStatusAttribute(): string
    {
        if (!empty($this->doctor_consulted_by) && !in_array(trim((string) $this->diagnosis), [self::DOCTOR_PLACEHOLDER_DIAGNOSIS, 'For doctor assessment'], true)) {
            return 'completed';
        }

        return 'waiting_for_doctor';
    }

    public function scopeLatestPerPatient(Builder $query): Builder
    {
        return $query->whereIn('id', function ($subQuery) {
            $subQuery->selectRaw('MAX(id)')
                ->from('clinic_records')
                ->groupBy('first_name', 'last_name', 'birthday');
        });
    }

    /**
     * Latest visit per patient that is already published and has no undispensed medicine lines.
     * Newer doctor/nurse rows stay off the registry until BHW publishes them; the list then shows
     * the previous published visit for that patient instead of hiding the patient entirely.
     *
     * BHW intake rows (placeholder diagnosis, no doctor/nurse signer) never appear here. A row must
     * have doctor_consulted_by set so only EMR consultations released by BHW count as registry entries.
     */
    public function scopeLatestPerPatientRegistryVisible(Builder $query): Builder
    {
        return $query->whereIn('id', function ($subQuery) {
            $subQuery->select(DB::raw('MAX(cr.id)'))
                ->from('clinic_records as cr')
                ->whereNotNull('cr.published_to_registry_at')
                ->whereNotNull('cr.doctor_consulted_by')
                ->where('cr.diagnosis', '!=', self::DOCTOR_PLACEHOLDER_DIAGNOSIS)
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('clinic_record_medicine as crm')
                        ->whereColumn('crm.clinic_record_id', 'cr.id')
                        ->whereNull('crm.dispensed_at');
                })
                ->groupBy('cr.first_name', 'cr.last_name', 'cr.birthday');
        });
    }

    public function scopeForBhwDashboard(Builder $query): Builder
    {
        return $query->latestPerPatient()
            ->orderBy('consultation_date', 'desc')
            ->orderBy('id', 'desc');
    }

    public function scopeForDoctorNurseDashboard(Builder $query): Builder
    {
        return $query->latestPerPatient()
            ->orderBy('consultation_date', 'desc')
            ->orderBy('id', 'desc');
    }
}
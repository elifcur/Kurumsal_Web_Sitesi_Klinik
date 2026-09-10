import Alpine, { startAlpine } from '../../../resources/js/site.js';

/*
 * Randevu formu: bölüm seçilince "Uzmanlık" özelliği o bölümle eşleşmeyen
 * doktorlar pasifleşir; eşleşen doktor yoksa tüm liste açık kalır.
 */
Alpine.data('appointmentForm', ({ doctors = [], services = [], serviceId = '', doctorId = '' } = {}) => ({
    doctors,
    services,
    serviceId: serviceId === null ? '' : String(serviceId),
    doctorId: doctorId === null ? '' : String(doctorId),
    get matchingIds() {
        const service = this.services.find((s) => String(s.id) === this.serviceId);
        if (!service) return null;
        const needle = service.title.toLocaleLowerCase('tr');
        const ids = this.doctors.filter((d) => (d.specialty || '').toLocaleLowerCase('tr').includes(needle)).map((d) => String(d.id));
        return ids.length ? ids : null;
    },
    matches(id) {
        return this.matchingIds === null || this.matchingIds.includes(String(id));
    },
    onServiceChange() {
        if (this.doctorId && !this.matches(this.doctorId)) {
            this.doctorId = '';
        }
    },
}));

startAlpine();

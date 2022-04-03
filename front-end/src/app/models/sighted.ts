export class Sighted {

    public last_seen: string
    public data_sighted: string
    public pet_id: number

    getLastSeen(): any {
        return this.last_seen;
    }
    setLastSeen(last_seen: any) {
        this.last_seen = last_seen;
    }
    getDataSighted(): any {
        return this.data_sighted;
    }
    setDataSighted(data_sighted: any) {
        this.data_sighted = data_sighted;
    }
    getPetId(): any {
        return this.pet_id;
    }
    setPetId(pet_id: any) {
        this.pet_id = pet_id;
    }
}

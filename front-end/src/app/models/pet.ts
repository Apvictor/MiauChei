export class Pet {

    public name: string
    public species: string
    public sex: string
    public breed: string
    public size: string
    public predominant_color: string
    public secondary_color: string
    public physical_details: string
    public date_disappearance: string
    public photo: string
    public status_id: string
    public id: string
    public user_id: number

    getUserId(): any {
        return this.user_id;
    }
    setUserId(user_id: any) {
        this.user_id = user_id;
    }
    getId(): any {
        return this.id;
    }
    setId(id: any) {
        this.id = id;
    }
    getName(): any {
        return this.name;
    }
    setName(name: any) {
        this.name = name;
    }
    getSpecies(): any {
        return this.species;
    }
    setSpecies(species: any) {
        this.species = species;
    }
    getSex(): any {
        return this.sex;
    }
    setSex(sex: any) {
        this.sex = sex;
    }
    getBreed(): any {
        return this.breed;
    }
    setBreed(breed: any) {
        this.breed = breed;
    }
    getSize(): any {
        return this.size;
    }
    setSize(size: any) {
        this.size = size;
    }
    getPredominant_color(): any {
        return this.predominant_color;
    }
    setPredominant_color(predominant_color: any) {
        this.predominant_color = predominant_color;
    }
    getSecondary_color(): any {
        return this.secondary_color;
    }
    setSecondary_color(secondary_color: any) {
        this.secondary_color = secondary_color;
    }
    getPhysical_details(): any {
        return this.physical_details;
    }
    setPhysical_details(physical_details: any) {
        this.physical_details = physical_details;
    }
    getDate_disappearance(): any {
        return this.date_disappearance;
    }
    setDate_disappearance(date_disappearance: any) {
        this.date_disappearance = date_disappearance;
    }
    getPhoto(): any {
        return this.photo;
    }
    setPhoto(photo: any) {
        this.photo = photo;
    }


}

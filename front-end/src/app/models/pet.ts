export class Pet {

    public id: number
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
    public uuid: string
    public created_at: string
    public updated_at: string
    public user_id: string
    public status_id: string


    getName(): any {
        return this.name;
    }
    setName(name: any) {
        this.name = name;
    }


}

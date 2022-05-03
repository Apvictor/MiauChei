export class User {

    public id: number
    public name: string
    public email: string
    public password: string
    public device_name: string
    public phone: string
    public photo: string

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
    getEmail(): string {
        return this.email;
    }
    setEmail(email: string) {
        this.email = email;
    }
    getPassword(): string {
        return this.password;
    }
    setPassword(password: string) {
        this.password = password;
    }
    getDevice_name(): string {
        return this.device_name;
    }
    setDevice_name(device_name: string) {
        this.device_name = device_name;
    }
    getPhone(): string {
        return this.phone;
    }
    setPhone(phone: string) {
        this.phone = phone;
    }
    getPhoto(): string {
        return this.photo;
    }
    setPhoto(photo: string) {
        this.photo = photo;
    }
}

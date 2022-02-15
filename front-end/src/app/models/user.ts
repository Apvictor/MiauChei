export class User {

    private name: string
    private email: string
    private password: string
    private device_name: string
    private phone: string
    private photo: string

    public constructor() {
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

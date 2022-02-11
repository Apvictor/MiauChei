export class User {

    private usuario = {
        email: "",
        senha: "",
        device_name: ""
    }

    //Métodos usuario
    public getUsuarioData() {
        return this.usuario
    }

    public setUsuarioData(data) {
        this.usuario = data
        this.usuario.device_name = 'Android'
    }
}

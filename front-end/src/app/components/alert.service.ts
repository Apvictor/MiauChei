import { Injectable } from '@angular/core';
import { ToastController, AlertController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class AlertService {

  constructor(
    private toast: ToastController,
    private alert: AlertController
  ) { }

  async showAlertError(err) {
    const toast = await this.toast.create({
      message: err.error.message,
      color: "danger",
      position: "top",
      duration: 2000
    });
    toast.present();
  }

  async showAlertSuccess(msg) {
    const toast = await this.toast.create({
      message: msg,
      color: "success",
      position: "top",
      duration: 2000
    });
    toast.present();
  }

  async alertLogout() {

    let dataVar
    let status = false;

    let alert = await this.alert.create({
      header: 'Sair do App',
      message: 'Deseja realmente sair?',
      buttons: [
        {
          text: 'Não',
          role: 'cancel',
          id: 'cancel-button',
          handler: () => {
            status = false;
            alert.dismiss();
          }
        }, {
          text: 'Sim',
          id: 'confirm-button',
          handler: () => {
            status = true;
          }
        }
      ]
    });

    await alert.present();

    await alert.onDidDismiss()
      .then(() => {
        status == true ? dataVar = true : dataVar = false;
      })

    return dataVar
  }

}

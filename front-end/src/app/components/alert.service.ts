import { Injectable } from '@angular/core';
import { ToastController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class AlertService {

  constructor(
    private toast: ToastController
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

}

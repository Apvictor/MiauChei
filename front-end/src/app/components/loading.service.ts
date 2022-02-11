import { Injectable } from '@angular/core';
import { LoadingController } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class LoadingService {

  constructor(public loadingController: LoadingController) { }

  async presentLoading() {
    const loading = await this.loadingController.create({
      cssClass: 'my-custom-class',
      message: 'Carregando...',
      duration: 2000
    });
    await loading.present();
  }

  async dismissLoading() {
    await this.loadingController.dismiss();
  }
}

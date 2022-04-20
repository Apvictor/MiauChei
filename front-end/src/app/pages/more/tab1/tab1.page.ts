import { AlertService } from './../../../components/alert.service';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component } from '@angular/core';
@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss']
})
export class Tab1Page {
  nameUser
  animais: any[];

  constructor(
    private pets: PetsService,
    private loading: LoadingService,
    private alert: AlertService,
  ) { }

  ionViewDidEnter() {
    this.animais = [];
    this.nameUser = window.localStorage.getItem('user-name');
    this.recentsPets();
  }

  recentsPets() {
    try {
      this.pets.recents()
        .then((res: any) => {
          // this.loading.presentLoading();
          for (let i = 0; i < res.length; i++) {
            let pet = res[i];
            this.animais.push(pet);
          }
        }).catch((err) => {
          window.location.reload();
          // this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }
}
import { AlertService } from './../../../components/alert.service';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component } from '@angular/core';
import { Storage } from '@capacitor/storage';

const USER_NAME = 'user-name';
@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss']
})
export class Tab1Page {
  nameUser
  animais

  constructor(
    private pets: PetsService,
    private loading: LoadingService,
    private alert: AlertService,
  ) { }

  ngOnInit() {
    this.getNameUser();
    this.recentsPets();
  }

  ionViewCanEnter() {
    this.recentsPets();
  }

  async getNameUser() {
    const name = await Storage.get({ key: USER_NAME });
    this.nameUser = name.value;
  }

  recentsPets() {
    this.loading.presentLoading();
    try {
      this.pets.recents()
        .then((res) => {
          this.animais = res;
        }).catch((err) => {
          this.alert.showAlertError(err);
        }).finally(() => {
          this.loading.dismissLoading();
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  doRefresh(event) {
    setTimeout(() => {
      this.recentsPets();
      this.getNameUser();
      event.target.complete();
    }, 1000);
  }
}
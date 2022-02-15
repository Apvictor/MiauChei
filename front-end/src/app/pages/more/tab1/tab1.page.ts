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
  ) { }

  ngOnInit() {
    this.getNameUser();

    this.recentPets();
  }

  async getNameUser() {
    const name = await Storage.get({ key: USER_NAME });
    this.nameUser = name.value;
  }

  async recentPets() {
    this.loading.presentLoading();
    try {
      this.pets.recent().then((pets) => {
        this.loading.dismissLoading();
        this.animais = pets;
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }
}




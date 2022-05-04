import { Device } from '@capacitor/device';
import { UserService } from './../../../services/user.service';
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
    private userService: UserService,
  ) {
    Device.getInfo().then((res) => {
      this.userService.notification.device_type = res.model;
    });
  }

  ionViewDidEnter() {
    this.animais = [];
    this.nameUser = window.localStorage.getItem('user-name');
    this.recentsPets();
    this.registerDevice();
  }

  recentsPets() {
    try {
      this.pets.recents()
        .then((res: any) => {
          for (let i = 0; i < res.length; i++) {
            let pet = res[i];
            this.animais.push(pet);
          }
        }).catch((err) => {
          window.location.reload();
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }


  registerDevice() {
    try {
      this.userService.postDevice(this.userService.notification)
        .then((res) => { console.log(res) });
    } catch (error) {
      console.log(error);
    }
  }
}
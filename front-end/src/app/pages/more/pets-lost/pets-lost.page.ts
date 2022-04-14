import { AlertService } from './../../../components/alert.service';
import { Pet } from './../../../models/pet';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-pets-lost',
  templateUrl: './pets-lost.page.html',
  styleUrls: ['./pets-lost.page.scss'],
})
export class PetsLostPage implements OnInit {

  animais

  constructor(
    private pets: PetsService,
    private loading: LoadingService,
    private alert: AlertService,
  ) { }

  ngOnInit() {
    this.petsLost();
  }

  async petsLost() {
    try {
      this.pets.petsLost()
        .then((res) => {
          this.loading.presentLoading();
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
      this.petsLost();
      event.target.complete();
    }, 1000);
  }

}

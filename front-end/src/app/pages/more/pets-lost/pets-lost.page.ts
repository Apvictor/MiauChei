import { AlertService } from './../../../components/alert.service';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-pets-lost',
  templateUrl: './pets-lost.page.html',
  styleUrls: ['./pets-lost.page.scss'],
})
export class PetsLostPage {

  animais: any[];
  filterTerm: string;

  constructor(
    private petsService: PetsService,
    private loading: LoadingService,
    private alert: AlertService,
  ) { }

  ionViewDidEnter() {
    this.animais = [];
    this.petsLost();
  }

  petsLost() {
    try {
      this.petsService.petsLost()
        .then((res: any) => {
          // this.loading.presentLoading();
          for (let i = 0; i < res.length; i++) {
            let pet = res[i];
            this.animais.push(pet);
          }
        }).catch((err) => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

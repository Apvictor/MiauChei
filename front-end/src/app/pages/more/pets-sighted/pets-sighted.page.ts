import { AlertService } from './../../../components/alert.service';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-pets-sighted',
  templateUrl: './pets-sighted.page.html',
  styleUrls: ['./pets-sighted.page.scss'],
})
export class PetsSightedPage {

  animais: any[];
  filterTerm: string;

  constructor(
    private pets: PetsService,
    private loading: LoadingService,
    private alert: AlertService,
  ) { }

  ionViewDidEnter() {
    this.animais = [];
    this.petsSighted();
  }

  petsSighted() {
    try {
      this.pets.petsSighted()
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

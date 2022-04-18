import { AlertService } from './../../../components/alert.service';
import { PetsService } from './../../../services/pets.service';
import { LoadingService } from './../../../components/loading.service';
import { Component, } from '@angular/core';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss']
})
export class Tab2Page {

  animais: any[];

  constructor(
    private loading: LoadingService,
    private pets: PetsService,
    private alert: AlertService,
  ) { }

  ionViewDidEnter() {
    this.animais = [];
    this.myPets();
  }

  myPets() {
    try {
      this.pets.myPets()
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

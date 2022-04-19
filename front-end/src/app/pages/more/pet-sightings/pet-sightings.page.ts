import { AlertService } from './../../../components/alert.service';
import { ActivatedRoute } from '@angular/router';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-pet-sightings',
  templateUrl: './pet-sightings.page.html',
  styleUrls: ['./pet-sightings.page.scss'],
})
export class PetSightingsPage {
  animais: any[];
  avistamentos: any[];

  constructor(
    private petsService: PetsService,
    private loading: LoadingService,
    private route: ActivatedRoute,
    private alert: AlertService,
  ) { }

  ionViewDidEnter() {
    this.animais = [];
    this.avistamentos = [];

    this.route.params.subscribe(parametros => {
      if (parametros['id']) {
        this.petSightings(parametros['id']);
      }
    })
  }

  petSightings(id) {
    try {
      this.petsService.petSightings(id)
        .then((res: any) => {
          // this.loading.presentLoading();

          for (let i = 0; i < res.length; i++) {
            this.animais.push(res[i]);            
            for (let y = 0; y < this.animais[i]['sighted'].length; y++) {
              this.avistamentos.push(this.animais[i]['sighted'][y]);
            }
          }
        }).catch((err) => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

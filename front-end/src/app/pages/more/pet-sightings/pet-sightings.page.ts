import { Sighted } from './../../../models/sighted';
import { ActivatedRoute } from '@angular/router';
import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-pet-sightings',
  templateUrl: './pet-sightings.page.html',
  styleUrls: ['./pet-sightings.page.scss'],
})
export class PetSightingsPage implements OnInit {
  animais
  avistamentos

  constructor(
    private petsService: PetsService,
    private loading: LoadingService,
    private route: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.route.params.subscribe(parametros => {
      if (parametros['id']) {
        this.petSightings(parametros['id']);
      }
    })
  }

  async petSightings(id) {
    this.loading.presentLoading();
    try {
      this.petsService.petSightings(id).then((pets) => {
        this.loading.dismissLoading();
        this.animais = pets;
        this.avistamentos = pets[0].sighted;
        console.log(pets);
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

import { Sighted } from './../../../models/sighted';
import { Time } from './../../../models/time';
import { ActivatedRoute } from '@angular/router';
import { Pet } from './../../../models/pet';
import { PetsService } from './../../../services/pets.service';
import { LoadingService } from './../../../components/loading.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-pets-details',
  templateUrl: './pets-details.page.html',
  styleUrls: ['./pets-details.page.scss'],
})
export class PetsDetailsPage implements OnInit {

  pet = new Pet()
  time = new Time
  sighted = new Sighted

  constructor(
    private petsService: PetsService,
    private loading: LoadingService,
    private route: ActivatedRoute,
  ) { }

  ngOnInit() {
    this.route.params.subscribe(parametros => {
      if (parametros['id']) {
        this.petsDetails(parametros['id']);
      }
    })
  }

  petsDetails(id) {
    this.loading.presentLoading();
    try {
      this.petsService.petsDetails(id).then((pets) => {
        this.loading.dismissLoading();
        this.pet.name = pets['name'];
        this.pet.photo = pets['photo'];
        this.pet.species = pets['species'];
        this.pet.sex = pets['sex'];
        this.pet.breed = pets['breed'];
        this.pet.size = pets['size'];
        this.pet.predominant_color = pets['predominant_color'];
        this.pet.status_id = pets['status_id'];
        this.pet.name = pets['name'];

        this.time.dias = pets['times']['dias']
        this.sighted.last_seen = pets['sighted']['last_seen']
        this.sighted.data_sighted = pets['sighted']['data_sighted']
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

import { NavController } from '@ionic/angular';
import { AlertService } from './../../../components/alert.service';
import { User } from './../../../models/user';
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
  user = new User()
  time = new Time()
  sighted = new Sighted()

  constructor(
    private petsService: PetsService,
    private loading: LoadingService,
    private route: ActivatedRoute,
    private alert: AlertService,
    private nav: NavController
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
        this.pet.id = pets['id'];
        this.pet.name = pets['name'];
        this.pet.photo = pets['photo'];
        this.pet.species = pets['species'];
        this.pet.sex = pets['sex'];
        this.pet.breed = pets['breed'];
        this.pet.size = pets['size'];
        this.pet.predominant_color = pets['predominant_color'];
        this.pet.status_id = pets['status_id'];
        this.pet.name = pets['name'];
        this.pet.date_disappearance = pets['date_disappearance'];
        this.pet.user_id = pets['user_id'];

        this.time.dias = pets['times']['dias']

        this.sighted.last_seen = pets['sighted']['last_seen']
        this.sighted.data_sighted = pets['sighted']['data_sighted']
        this.sighted.user_pet = pets['sighted']['user_pet']

        this.user.phone = pets['user']['phone']
        this.user.name = pets['user']['name']
        this.user.photo = pets['user']['photo']
        this.user.id = pets['user']['id']
      })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  petFound(id) {
    try {
      this.petsService.petFound(id)
        .then(() => {
          this.nav.back();
        })
        .catch(err => {
          this.alert.showAlertError(err);
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

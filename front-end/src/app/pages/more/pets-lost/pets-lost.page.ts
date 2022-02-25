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
  ) { }

  ngOnInit() {
    this.petsLost();
  }

  async petsLost() {
    this.loading.presentLoading();
    try {
      this.pets.petsLost().then((pets) => {
        this.loading.dismissLoading();
        this.animais = pets;
        console.log(pets);
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

}

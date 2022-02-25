import { LoadingService } from './../../../components/loading.service';
import { PetsService } from './../../../services/pets.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-pets-sighted',
  templateUrl: './pets-sighted.page.html',
  styleUrls: ['./pets-sighted.page.scss'],
})
export class PetsSightedPage implements OnInit {

  animais

  constructor(
    private pets: PetsService,
    private loading: LoadingService,
  ) { }

  ngOnInit() {
    this.petsSighted();
  }

  async petsSighted() {
    this.loading.presentLoading();
    try {
      this.pets.petsSighted().then((pets) => {
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

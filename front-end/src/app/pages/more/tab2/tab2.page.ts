import { PetsService } from './../../../services/pets.service';
import { LoadingService } from './../../../components/loading.service';
import { Component, } from '@angular/core';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss']
})
export class Tab2Page {

  animais

  constructor(
    private loading: LoadingService,
    private pets: PetsService,
  ) { }

  ngOnInit() {
    this.myPets();
  }

  async myPets() {
    this.loading.presentLoading();
    try {
      this.pets.myPets().then((pets) => {
        this.loading.dismissLoading();
        this.animais = pets;
      })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  doRefresh(event) {
    setTimeout(() => {
      this.myPets();
      event.target.complete();
    }, 1000);
  }
}

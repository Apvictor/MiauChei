import { PetsService } from './../../../services/pets.service';
import { LoadingService } from './../../../components/loading.service';
import { Component, } from '@angular/core';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss']
})
export class Tab2Page {

  animais = []
  items = []

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
        this.animais['item'] = pets;

        for (let i = 0; i < this.animais['item'].length; i++) {
          this.items.push(pets[i]);
        }
      })
        .catch(err => {
        })
    } catch (err) {
      console.log("erro " + err)
    }
  }

  loadData(event) {
    setTimeout(() => {
      console.log('Done');
      this.myPets();
      event.target.complete();
      if (this.animais.length == 5) {
        event.target.disabled = true;
      }
    }, 500);
  }
}

import { NavController } from '@ionic/angular';
import { Component } from '@angular/core';

@Component({
  selector: 'app-splash',
  templateUrl: './splash.page.html',
  styleUrls: ['./splash.page.scss'],
})
export class SplashPage {

  constructor(private nav: NavController) {
    setTimeout(() => {
      this.nav.navigateForward('login');
    }, 1200)
  }

}

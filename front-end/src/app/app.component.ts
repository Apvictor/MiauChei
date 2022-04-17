import { NavController } from '@ionic/angular';
import { Component } from '@angular/core';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  token

  constructor(
    public nav: NavController,
  ) {
    this.initializeApp()
  }

  initializeApp() {
    this.getToken();
    if (this.token) {
      this.nav.navigateForward("");
    } else {
      this.nav.navigateForward("splash");
    }
  }

  getToken() {
    const token = window.localStorage.getItem('CapacitorStorage.access-token');
    this.token = token
    return this.token;
  }
}

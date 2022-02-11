import { NavController } from '@ionic/angular';
import { Component } from '@angular/core';
import { Storage } from '@capacitor/storage';

const USER_NAME = 'user-name';
const ACCESS_TOKEN_KEY = 'access-token';
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
      this.nav.navigateForward("login");
    }
  }

  getToken() {
    const token = window.localStorage.getItem('CapacitorStorage.access-token');
    this.token = token
    return this.token;
  }
}

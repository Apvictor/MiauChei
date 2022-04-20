import { AuthService } from './services/auth.service';
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
    private auth: AuthService,
    private nav: NavController,
  ) {
    this.initializeApp();
  }

  initializeApp() {
    this.token = window.localStorage.getItem('access-token');
    this.autoLogin();
  }

  autoLogin() {
    this.auth.isAuthenticated.subscribe(state => {
      if (this.token != null) {
        state != false ? this.nav.navigateRoot('') : this.nav.navigateRoot('/login');
      } else {
        this.nav.navigateRoot('/login')
      }
    });
  }
}

import { AuthService } from './services/auth.service';
import { NavController } from '@ionic/angular';
import { Component } from '@angular/core';
import { SplashScreen } from '@capacitor/splash-screen';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {

  constructor(
    private auth: AuthService,
    private nav: NavController,
  ) {
    this.initializeApp();
  }

  async initializeApp() {
    this.autoLogin();
    await SplashScreen.hide();
  }

  async autoLogin() {
    this.auth.isAuthenticated.subscribe(state => {
      if (state) {
        this.nav.navigateRoot('');
      } else {
        this.nav.navigateRoot('/login');
      }
    });
  }
}

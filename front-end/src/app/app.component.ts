import { UserService } from './services/user.service';
import { Device } from '@capacitor/device';
import { AuthService } from './services/auth.service';
import { NavController, Platform } from '@ionic/angular';
import { Component } from '@angular/core';
import OneSignal from 'onesignal-cordova-plugin';
import { Notification } from './models/notification';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {

  token

  constructor(
    private auth: AuthService,
    private userService: UserService,
    private nav: NavController,
    private platform: Platform,
  ) {
    platform.ready().then(() => {
      this.OneSignalInit();
    });
    this.initializeApp();
  }

  initializeApp() {
    this.token = window.localStorage.getItem('access-token');
    this.autoLogin();
  }

  OneSignalInit() {
    OneSignal.setAppId("ee6a0325-7033-4250-875d-bbd93cd9bb25");
    OneSignal.setNotificationOpenedHandler(function (jsonData) {
      console.log('notificationOpenedCallback: ' + JSON.stringify(jsonData));
    });

    OneSignal.promptForPushNotificationsWithUserResponse(function (accepted) {
      console.log("User accepted notifications: " + accepted);
    });

    OneSignal.getDeviceState((stateChanges) => {
      console.log(stateChanges.userId);
      this.userService.notification.os_player_id = stateChanges.userId;
    });
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

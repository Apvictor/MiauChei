import { Injectable } from '@angular/core';
import { CanActivate } from '@angular/router';
import { Observable } from 'rxjs';
import { filter, map, take } from 'rxjs/operators'
import { NavController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class LoginGuard implements CanActivate {

  constructor(
    private nav: NavController,
    private auth: AuthService
  ) { }

  canActivate(): Observable<boolean> {
    return this.auth.isAuthenticated.pipe(
      filter(val => val !== null),
      take(1),
      map(isAuthenticated => {
        if (isAuthenticated) {
          return true
        } else {
          this.nav.navigateRoot('/login')
          return false
        }
      })
    )
  }

}

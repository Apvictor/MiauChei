import { Injectable } from '@angular/core';
import { ApiService } from './api.service';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  isAuthenticated: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(null);
  currentAccessToken = null;

  constructor(private api: ApiService) { }

  login(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/login', data).subscribe((res: any) => {
        window.localStorage.setItem('user-id', res.user.id);
        window.localStorage.setItem('user-name', res.user.name);
        window.localStorage.setItem('access-token', res.authorization);
        window.localStorage.setItem('refresh-token', res.authorization);
        this.currentAccessToken = res.authorization;
        this.isAuthenticated.next(true)
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  cadastro(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/register', data).subscribe((res: any) => {
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  logout(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/logout', data).subscribe((res: any) => {
        this.isAuthenticated.next(false)
        window.localStorage.clear();
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  loginFacebook() {
    return new Promise((resolve, reject) => {
      this.api.get('/login/facebook').subscribe((res: any) => {
        // window.localStorage.setItem('user-id', res.user.id);
        // window.localStorage.setItem('user-name', res.user.name);
        // window.localStorage.setItem('access-token', res.authorization);
        // window.localStorage.setItem('refresh-token', res.authorization);
        // this.currentAccessToken = res.authorization;
        // this.isAuthenticated.next(true)
        resolve(res)
        console.log(res);

      }, err => {
        reject(err)
      })
    })
  }
}

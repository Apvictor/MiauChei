import { Injectable } from '@angular/core';
import { ApiService } from './api.service';
import { BehaviorSubject } from 'rxjs';
import { Storage } from '@capacitor/storage';

const ACCESS_TOKEN_KEY = 'access-token';
const USER_ID = 'user-id';
const USER_NAME = 'user-name';

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
        Storage.set({ key: ACCESS_TOKEN_KEY, value: res.authorization })
        Storage.set({ key: USER_NAME, value: res.user.name })
        Storage.set({ key: USER_ID, value: res.user.id })
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
        Storage.remove({ key: ACCESS_TOKEN_KEY })
        Storage.remove({ key: USER_ID })
        Storage.remove({ key: USER_NAME })
        this.currentAccessToken = null;
        this.isAuthenticated.next(false)
        localStorage.clear();
        window.location.reload();
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

}

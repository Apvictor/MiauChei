import { Injectable } from '@angular/core';
import { ApiService } from './api.service';
import { BehaviorSubject } from 'rxjs';
import { Storage } from '@capacitor/storage';

const ACCESS_TOKEN_KEY = 'access-token';
const REFRESH_TOKEN_KEY = 'my-refresh-token';
const USER_ID = 'user-id';
const USER_NAME = 'user-name';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  isAuthenticated: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(null);
  currentAccessToken = null;

  constructor(
    private api: ApiService,
  ) { }

  login(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/login', data).subscribe((res: any) => {
        Storage.set({ key: ACCESS_TOKEN_KEY, value: res.authorization })
        Storage.set({ key: REFRESH_TOKEN_KEY, value: res.authorization })
        Storage.set({ key: USER_NAME, value: res.user.name })
        Storage.set({ key: USER_ID, value: res.user.id })
        this.currentAccessToken = res.authorization;
        this.isAuthenticated.next(true)
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

}

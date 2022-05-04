import { ApiService } from './api.service';
import { Injectable } from '@angular/core';
import { Notification } from '../models/notification';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  public notification = new Notification();

  constructor(private api: ApiService) { }

  profile() {
    return new Promise((resolve, reject) => {
      this.api.get('/profile').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  postProfile(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/profile', data).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  postDevice(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/notification/register/device', data).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
        console.error(err);
      })
    })
  }
}

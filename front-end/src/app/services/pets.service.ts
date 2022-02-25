import { ApiService } from './api.service';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PetsService {

  constructor(
    private api: ApiService,
  ) { }

  /**
   * Retorno de pets cadastrados recentemente
   * @returns 
   */
  recents() {
    return new Promise((resolve, reject) => {
      this.api.get('/recents').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Retorno de pets desaparecidos
   * @returns 
   */
  petsLost() {
    return new Promise((resolve, reject) => {
      this.api.get('/pets-lost').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Retorno de pets avistados
   * @returns 
   */
  petsSighted() {
    return new Promise((resolve, reject) => {
      this.api.get('/pets-sighted').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Retorno dos meus Pets Cadastrados
   * @returns 
   */
  myPets() {
    return new Promise((resolve, reject) => {
      this.api.get('/mypets').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Retorno dos detalhes do pet conforme id passado
   * @returns 
   */
  petsDetails(id) {
    return new Promise((resolve, reject) => {
      this.api.get('/pets-details/' + id).subscribe((res: any) => {
        // console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

}

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
   */
  petsDetails(id) {
    return new Promise((resolve, reject) => {
      this.api.get('/pets-details/' + id).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Cadastro de pets perdidos 
   */
  petsStore(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/pets-store', data).subscribe((res: any) => {
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Retorno dos avistamentos do pet
   */
  petSightings(id) {
    return new Promise((resolve, reject) => {
      this.api.get('/pet-sightings/' + id).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
   * Cadastro de avistamentos
   */
  petsSightedStore(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/pets-sighted-store', data).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  /**
 * Cadastro de avistamentos
 */
  petFound(id, data = null) {
    return new Promise((resolve, reject) => {
      this.api.put('/pet-found/' + id, data).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

}

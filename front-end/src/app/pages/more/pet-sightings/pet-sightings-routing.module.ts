import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PetSightingsPage } from './pet-sightings.page';

const routes: Routes = [
  {
    path: '',
    component: PetSightingsPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PetSightingsPageRoutingModule {}

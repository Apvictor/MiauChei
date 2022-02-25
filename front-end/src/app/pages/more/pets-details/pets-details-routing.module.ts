import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PetsDetailsPage } from './pets-details.page';

const routes: Routes = [
  {
    path: '',
    component: PetsDetailsPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PetsDetailsPageRoutingModule {}

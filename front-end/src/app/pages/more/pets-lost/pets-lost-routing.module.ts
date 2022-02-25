import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PetsLostPage } from './pets-lost.page';

const routes: Routes = [
  {
    path: '',
    component: PetsLostPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PetsLostPageRoutingModule {}

import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CadastroPetPage } from './cadastro-pet.page';

const routes: Routes = [
  {
    path: '',
    component: CadastroPetPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CadastroPetPageRoutingModule {}

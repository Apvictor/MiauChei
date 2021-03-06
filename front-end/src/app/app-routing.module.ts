import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    loadChildren: () => import('./pages/more/tabs/tabs.module').then(m => m.TabsPageModule)
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/auth/login/login.module').then(m => m.LoginPageModule)
  },
  {
    path: 'confirmacao',
    loadChildren: () => import('./modals/confirmacao/confirmacao.module').then(m => m.ConfirmacaoPageModule)
  },
  {
    path: 'cadastro',
    loadChildren: () => import('./pages/auth/cadastro/cadastro.module').then(m => m.CadastroPageModule)
  },
  {
    path: 'cadastro-foto',
    loadChildren: () => import('./pages/auth/cadastro-foto/cadastro-foto.module').then(m => m.CadastroFotoPageModule)
  },
  {
    path: 'pets-lost',
    loadChildren: () => import('./pages/more/pets-lost/pets-lost.module').then(m => m.PetsLostPageModule)
  },
  {
    path: 'pets-sighted',
    loadChildren: () => import('./pages/more/pets-sighted/pets-sighted.module').then(m => m.PetsSightedPageModule)
  },
  {
    path: 'pets-details/:tipo/:id',
    loadChildren: () => import('./pages/more/pets-details/pets-details.module').then(m => m.PetsDetailsPageModule)
  },
  {
    path: 'cadastro-pet',
    loadChildren: () => import('./pages/more/cadastro-pet/cadastro-pet.module').then( m => m.CadastroPetPageModule)
  },
  {
    path: 'pet-sightings/:id',
    loadChildren: () => import('./pages/more/pet-sightings/pet-sightings.module').then( m => m.PetSightingsPageModule)
  },
  {
    path: 'cadastro-sighted/:id',
    loadChildren: () => import('./pages/more/cadastro-sighted/cadastro-sighted.module').then( m => m.CadastroSightedPageModule)
  },  {
    path: 'splash',
    loadChildren: () => import('./pages/auth/splash/splash.module').then( m => m.SplashPageModule)
  },

];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }

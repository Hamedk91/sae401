import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddAutoEcoleComponent } from './add-auto-ecole.component';

describe('AddAutoEcoleComponent', () => {
  let component: AddAutoEcoleComponent;
  let fixture: ComponentFixture<AddAutoEcoleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AddAutoEcoleComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AddAutoEcoleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditAutoEcoleComponent } from './edit-auto-ecole.component';

describe('EditAutoEcoleComponent', () => {
  let component: EditAutoEcoleComponent;
  let fixture: ComponentFixture<EditAutoEcoleComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [EditAutoEcoleComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EditAutoEcoleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

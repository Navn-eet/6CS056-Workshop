<x-form-input name="name" label="Name" :value="$student->name" autocomplete="name" />

<x-form-input name="email" label="Email" type="email" :value="$student->email" autocomplete="email" />

<x-form-input name="phone" label="Phone" :value="$student->phone" autocomplete="tel" />

<x-form-textarea name="address" label="Address" :value="$student->address" autocomplete="street-address" />

<x-form-input name="date_of_birth" label="Date of Birth" type="date"
              :value="$student->date_of_birth?->format('Y-m-d')" autocomplete="off" />

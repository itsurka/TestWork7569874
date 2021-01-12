import {City} from "@/components/cars/models/City";
import {CarBrand} from "@/components/cars/models/CarBrand";
import {CarBrandModel} from "@/components/cars/models/CarBrandModel";
import {User} from "@/components/common/models/User";

export interface Car {
    id: number,
    title: string,
    description: string,
    city: City,
    brand: CarBrand,
    brand_model: CarBrandModel,
    prod_year: number,
    body_type: number,
    seats: number,
    fuel: number,
    engine_capacity: number,
    gearbox_type: number,
    wheel_drive: number,
    odometer: number,
    status: number,
    created_at: string,
    updated_at: string,
    owner: User
}
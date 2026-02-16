import { useState } from 'react';
import { Building2, Mail, DollarSign, MapPin, Plane } from 'lucide-react';

interface Hotel {
  name: string;
  email: string;
  priceRange: string;
  distance: string;
  nearAirport: boolean;
  category: 'budget' | 'mid-range' | 'premium';
  image: string;
}

const hotels: Hotel[] = [
  {
    name: 'Hotel Olathang',
    email: 'olathang@druknet.bt',
    priceRange: '$80-120',
    distance: '2.5 km to Dungkar Dzong',
    nearAirport: true,
    category: 'mid-range',
    image: 'https://images.unsplash.com/photo-1663583942499-f8cff8e49e04?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsdXh1cnklMjBCaHV0YW4lMjBob3RlbCUyMHJlc29ydHxlbnwxfHx8fDE3NzEyMjIyNjF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Tashi Namgay Resort',
    email: 'reservation@tashihotels.bt',
    priceRange: '$120-180',
    distance: '3 km to Dungkar Dzong',
    nearAirport: true,
    category: 'premium',
    image: 'https://images.unsplash.com/photo-1761048802383-ecade27b00fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcmVtaXVtJTIwcmVzb3J0JTIwQmh1dGFuJTIwbW91bnRhaW5zfGVufDF8fHx8MTc3MTIyMjI2MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Mandala Resort & Spa',
    email: 'info@mandalaresorts.com',
    priceRange: '$150-250',
    distance: '4 km to Dungkar Dzong',
    nearAirport: false,
    category: 'premium',
    image: 'https://images.unsplash.com/photo-1578658503040-8d375c771a37?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxCaHV0YW4lMjByZXNvcnQlMjBzcGElMjBsdXh1cnl8ZW58MXx8fHwxNzcxMjIyMjYxfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Khangkhu Resort',
    email: 'khangkhu@druknet.bt',
    priceRange: '$60-90',
    distance: '3.5 km to Dungkar Dzong',
    nearAirport: true,
    category: 'budget',
    image: 'https://images.unsplash.com/photo-1590761044169-b9ad903fca4d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxidWRnZXQlMjBob3RlbCUyMGNsZWFuJTIwbW9kZXJufGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Rema Resort',
    email: 'remaresort@druknet.bt',
    priceRange: '$100-140',
    distance: '2 km to Dungkar Dzong',
    nearAirport: true,
    category: 'mid-range',
    image: 'https://images.unsplash.com/photo-1733111248500-fe2ff4d3ccff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFkaXRpb25hbCUyMEJodXRhbiUyMGhvdGVsfGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Hotel Jomolhari',
    email: 'jomolhari@druknet.bt',
    priceRange: '$50-80',
    distance: '3 km to Dungkar Dzong',
    nearAirport: false,
    category: 'budget',
    image: 'https://images.unsplash.com/photo-1766852287368-01975b8ad3d9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb3VudGFpbiUyMHJlc29ydCUyMGhvdGVsJTIwYnVpbGRpbmd8ZW58MXx8fHwxNzcxMjIyMjYzfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Le Méridien Paro',
    email: 'reservations.paro@lemeridien.com',
    priceRange: '$200-350',
    distance: '5 km to Dungkar Dzong',
    nearAirport: false,
    category: 'premium',
    image: 'https://images.unsplash.com/photo-1663583942499-f8cff8e49e04?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxib3V0aXF1ZSUyMGhvdGVsJTIwQmh1dGFufGVufDF8fHx8MTc3MTIyMjI2Mnww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  },
  {
    name: 'Tenzinling Resort',
    email: 'info@tenzinling.com',
    priceRange: '$90-130',
    distance: '2.8 km to Dungkar Dzong',
    nearAirport: true,
    category: 'mid-range',
    image: 'https://images.unsplash.com/photo-1761048802383-ecade27b00fd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcmVtaXVtJTIwcmVzb3J0JTIwQmh1dGFuJTIwbW91bnRhaW5zfGVufDF8fHx8MTc3MTIyMjI2MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral'
  }
];

export function AccommodationTab() {
  const [filter, setFilter] = useState<'all' | 'budget' | 'mid-range' | 'premium'>('all');

  const filteredHotels = filter === 'all' 
    ? hotels 
    : hotels.filter(hotel => hotel.category === filter);

  return (
    <div className="max-w-6xl mx-auto space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Accommodation in Paro</h2>

      <div className="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <p className="text-gray-800">
          <strong>Important:</strong> All Summit participants must stay in Paro near the venue (Dungkar Dzong). Book early as August is peak season.
        </p>
      </div>

      {/* Filter Buttons */}
      <div className="flex flex-wrap gap-3">
        <button
          onClick={() => setFilter('all')}
          className={`px-4 py-2 rounded-lg font-medium transition-colors ${
            filter === 'all'
              ? 'bg-[#B91C1C] text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          }`}
        >
          All Hotels
        </button>
        <button
          onClick={() => setFilter('budget')}
          className={`px-4 py-2 rounded-lg font-medium transition-colors ${
            filter === 'budget'
              ? 'bg-[#B91C1C] text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          }`}
        >
          Budget ($50-90)
        </button>
        <button
          onClick={() => setFilter('mid-range')}
          className={`px-4 py-2 rounded-lg font-medium transition-colors ${
            filter === 'mid-range'
              ? 'bg-[#B91C1C] text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          }`}
        >
          Mid-Range ($90-150)
        </button>
        <button
          onClick={() => setFilter('premium')}
          className={`px-4 py-2 rounded-lg font-medium transition-colors ${
            filter === 'premium'
              ? 'bg-[#B91C1C] text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          }`}
        >
          Premium ($150+)
        </button>
      </div>

      {/* Hotel Cards Grid */}
      <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredHotels.map((hotel) => (
          <div
            key={hotel.name}
            className="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-all"
          >
            {/* Hotel Image */}
            <div className="h-48 overflow-hidden">
              <img 
                src={hotel.image}
                alt={hotel.name}
                className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
              />
            </div>

            <div className="p-6">
              <div className="flex items-start justify-between mb-4">
                <div className="flex items-center gap-3">
                  <div className="p-2 bg-amber-100 rounded-lg">
                    <Building2 className="w-5 h-5 text-amber-600" />
                  </div>
                  {hotel.nearAirport && (
                    <div className="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                      <Plane className="w-3 h-3" />
                      Airport
                    </div>
                  )}
                </div>
              </div>

              <h3 className="text-lg font-bold text-gray-900 mb-3">
                {hotel.name}
              </h3>

              <div className="space-y-3 text-sm">
                <div className="flex items-start gap-2 text-gray-700">
                  <Mail className="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" />
                  <a 
                    href={`mailto:${hotel.email}`}
                    className="hover:text-[#B91C1C] break-all"
                  >
                    {hotel.email}
                  </a>
                </div>

                <div className="flex items-start gap-2 text-gray-700">
                  <DollarSign className="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" />
                  <span className="font-medium">{hotel.priceRange} per night</span>
                </div>

                <div className="flex items-start gap-2 text-gray-700">
                  <MapPin className="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" />
                  <span>{hotel.distance}</span>
                </div>
              </div>

              <div className="mt-4 pt-4 border-t border-gray-100">
                <span className={`inline-block px-3 py-1 rounded-full text-xs font-medium ${
                  hotel.category === 'budget' 
                    ? 'bg-green-100 text-green-700'
                    : hotel.category === 'mid-range'
                    ? 'bg-blue-100 text-blue-700'
                    : 'bg-purple-100 text-purple-700'
                }`}>
                  {hotel.category.charAt(0).toUpperCase() + hotel.category.slice(1).replace('-', ' ')}
                </span>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Additional Info */}
      <div className="bg-amber-50 border border-amber-200 rounded-xl p-6">
        <h3 className="text-xl font-semibold text-gray-900 mb-3">
          Booking Tips
        </h3>
        <ul className="space-y-2 text-gray-700">
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>Contact hotels directly for Summit group rates</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>Book immediately after receiving visa approval</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>Mention you're attending the Summit for potential discounts</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-amber-600 mt-1">•</span>
            <span>Confirm cancellation policies before booking</span>
          </li>
        </ul>
      </div>
    </div>
  );
}
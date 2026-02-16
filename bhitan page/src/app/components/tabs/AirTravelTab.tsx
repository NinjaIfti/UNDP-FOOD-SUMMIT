import { AlertTriangle, ExternalLink, MapPin } from 'lucide-react';

export function AirTravelTab() {
  const airlines = [
    {
      name: 'Drukair',
      website: 'https://www.drukair.com.bt',
      description: 'Royal Bhutan Airlines'
    },
    {
      name: 'Bhutan Airlines',
      website: 'https://www.bhutanairlines.bt',
      description: 'Private carrier'
    }
  ];

  const routes = [
    'Bangkok',
    'Delhi',
    'Kathmandu',
    'Dhaka',
    'Singapore',
    'Dubai',
    'Kolkata'
  ];

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      <h2 className="text-3xl font-bold text-gray-900">Air Travel to Paro</h2>

      {/* Important Booking Guidance */}
      <div className="bg-red-50 border-2 border-red-300 rounded-xl p-6">
        <div className="flex gap-4">
          <AlertTriangle className="w-6 h-6 text-red-600 flex-shrink-0 mt-1" />
          <div className="space-y-3">
            <h3 className="text-xl font-semibold text-gray-900">
              Important Booking Guidance
            </h3>
            <ul className="space-y-2 text-gray-700">
              <li className="flex items-start gap-2">
                <span className="text-red-600 mt-1">•</span>
                <span><strong>Do not book flights before visa approval</strong></span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-red-600 mt-1">•</span>
                <span>August is high travel season — book early once approved</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-red-600 mt-1">•</span>
                <span>Limited daily flights operate to Paro</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Airlines */}
      <div>
        <h3 className="text-2xl font-semibold text-gray-900 mb-4">
          Airlines Operating to Paro
        </h3>
        <div className="grid md:grid-cols-2 gap-4">
          {airlines.map((airline, index) => (
            <div
              key={airline.name}
              className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow"
            >
              <div className="flex items-start justify-between mb-4">
                <div className="flex-1">
                  <h4 className="text-xl font-bold text-gray-900 mb-2">
                    {airline.name}
                  </h4>
                  <p className="text-gray-600 mb-4">{airline.description}</p>
                </div>
                <div className="ml-4 flex-shrink-0">
                  <div className="w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200">
                    <img 
                      src={index === 0 
                        ? "https://images.unsplash.com/photo-1761048163470-78cc1232e86b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxCaHV0YW4lMjBhaXJjcmFmdCUyMGFpcnBsYW5lfGVufDF8fHx8MTc3MTIyMjE1OHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
                        : "https://images.unsplash.com/photo-1520745405162-d4b5fb38df5d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb21tZXJjaWFsJTIwYWlybGluZSUyMGpldCUyMHBsYW5lfGVufDF8fHx8MTc3MTIyMjE2MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
                      }
                      alt={`${airline.name} logo`}
                      className="w-full h-full object-cover"
                    />
                  </div>
                </div>
              </div>
              <a
                href={airline.website}
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-4 py-2 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
              >
                Visit Website
                <ExternalLink className="w-4 h-4" />
              </a>
            </div>
          ))}
        </div>
      </div>

      {/* Flight Routes */}
      <div>
        <h3 className="text-2xl font-semibold text-gray-900 mb-4">
          List of available flights to Paro
        </h3>
        <div className="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="bg-gray-100 border-b border-gray-300">
                  <th className="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">
                    SECTOR
                  </th>
                  <th className="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">
                    Flight availability
                  </th>
                  <th className="px-4 py-3 text-left text-sm font-bold text-gray-900 border-r border-gray-300">
                    SAARC³ Regional Fare (USD)
                  </th>
                  <th className="px-4 py-3 text-left text-sm font-bold text-gray-900">
                    International Fare (USD)
                  </th>
                </tr>
              </thead>
              <tbody>
                {/* Drukair Section */}
                <tr className="border-b border-gray-200 bg-blue-50">
                  <td colSpan={4} className="px-4 py-3 text-center">
                    <a 
                      href="https://www.drukair.com.bt" 
                      target="_blank" 
                      rel="noopener noreferrer"
                      className="text-[#B91C1C] font-semibold hover:underline"
                    >
                      Drukair
                    </a>
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Bangkok-Paro-Bangkok<br />
                    <span className="text-gray-600 text-xs">(via Guwahati)</span>
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Daily
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Business class:<br />
                    Economy class:
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                    Business class:<br />
                    Economy class:
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Bangkok-Paro-Bangkok<br />
                    <span className="text-gray-600 text-xs">(Via Bagdogra)</span>
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    <strong>Tuesday & Saturday</strong><br />
                    (BKK → Bagdogra→ Paro)<br />
                    <strong>Monday & Friday</strong><br />
                    (Paro → Bagdogra → BKK)
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Delhi-Paro-Delhi
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Daily
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Kathmandu-Paro-Kathmandu
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Daily
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Dhaka-Paro-Dhaka
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Monday & Friday
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Singapore-Paro-Singapore
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Tuesdays, Thursdays,<br />
                    Fridays, Sundays<br />
                    <span className="text-gray-600 text-xs">(via Guwahati)</span>
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Dubai-Paro-Dubai
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    <strong>Tuesday, Friday,<br />
                    Saturday</strong> .<br />
                    <strong>Tuesdays, Sundays for<br />
                    August</strong>
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Kolkata-Paro-Kolkata
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                    Daily
                  </td>
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900">
                  </td>
                  <td className="px-4 py-4 text-sm text-gray-900">
                  </td>
                </tr>
                
                {/* Bhutan Airlines Section */}
                <tr className="border-b border-gray-200 bg-blue-50">
                  <td colSpan={4} className="px-4 py-3 text-center">
                    <a 
                      href="https://www.bhutanairlines.bt" 
                      target="_blank" 
                      rel="noopener noreferrer"
                      className="text-[#B91C1C] font-semibold hover:underline"
                    >
                      Bhutan Airlines
                    </a>
                  </td>
                </tr>
                <tr className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-4 border-r border-gray-200 text-sm text-gray-900" colSpan={4}>
                    <em className="text-gray-600">Please check Bhutan Airlines website for current flight schedules and availability.</em>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div className="flex items-start gap-3 bg-blue-50 p-4 border-t border-gray-200">
            <span className="text-blue-600 mt-1">ℹ️</span>
            <div className="text-sm text-gray-700">
              <p className="mb-2">
                <strong>Note:</strong> Flight schedules are subject to change. Always verify with the airline before booking.
              </p>
              <p>
                <strong>³ SAARC Regional Fare</strong> applies to citizens of: Afghanistan, Bangladesh, Bhutan, India, Maldives, Nepal, Pakistan, and Sri Lanka.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Transit Visa Warning */}
      <div className="bg-orange-50 border-2 border-orange-300 rounded-xl p-6">
        <div className="flex gap-4">
          <AlertTriangle className="w-6 h-6 text-orange-600 flex-shrink-0 mt-1" />
          <div className="space-y-4">
            <div>
              <h3 className="text-xl font-semibold text-gray-900 mb-2">
                Check Transit Visa Requirements
              </h3>
              <p className="text-gray-700">
                If you're transiting through India or Nepal, you may need a transit visa. Check requirements based on your nationality.
              </p>
            </div>
            <div className="flex flex-wrap gap-3">
              <a
                href="https://indianvisaonline.gov.in/evisa/tvoa.html"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-orange-400 text-gray-900 rounded-lg hover:bg-orange-50 transition-colors"
              >
                India Transit Visa
                <ExternalLink className="w-4 h-4" />
              </a>
              <a
                href="https://www.immigration.gov.np/page/transit-visa"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-orange-400 text-gray-900 rounded-lg hover:bg-orange-50 transition-colors"
              >
                Nepal Transit Visa
                <ExternalLink className="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
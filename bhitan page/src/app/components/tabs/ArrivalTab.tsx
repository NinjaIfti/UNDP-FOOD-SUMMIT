import { Smartphone, Banknote } from 'lucide-react';

export function ArrivalTab() {
  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Upon Arrival in Bhutan</h2>

      <div className="grid md:grid-cols-2 gap-6">
        {/* Mobile & Internet */}
        <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
          <div className="flex items-center gap-3 mb-4">
            <div className="p-3 bg-amber-100 rounded-lg">
              <Smartphone className="w-6 h-6 text-amber-600" />
            </div>
            <h3 className="text-xl font-semibold text-gray-900">
              Mobile & Internet
            </h3>
          </div>
          <div className="space-y-3 text-gray-700">
            <p>
              <strong>SIM cards available</strong> at Paro International Airport upon arrival
            </p>
            <div className="bg-gray-50 rounded-lg p-4 space-y-2">
              <p className="font-medium text-gray-900">Providers:</p>
              <ul className="space-y-1">
                <li className="flex items-start gap-2">
                  <span className="text-[#B91C1C]">•</span>
                  <span>Bhutan Telecom</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="text-[#B91C1C]">•</span>
                  <span>TashiCell</span>
                </li>
              </ul>
            </div>
            <div className="bg-amber-50 border border-amber-200 rounded-lg p-4">
              <p className="font-medium text-gray-900">Cost: BTN 100–500</p>
              <p className="text-sm text-gray-600 mt-1">
                Includes data package options
              </p>
            </div>
            <p className="text-sm text-gray-600">
              Bring your passport and an unlocked phone
            </p>
          </div>
        </div>

        {/* Currency Exchange */}
        <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
          <div className="flex items-center gap-3 mb-4">
            <div className="p-3 bg-green-100 rounded-lg">
              <Banknote className="w-6 h-6 text-green-600" />
            </div>
            <h3 className="text-xl font-semibold text-gray-900">
              Currency Exchange
            </h3>
          </div>
          <div className="space-y-3 text-gray-700">
            <div className="bg-gray-50 rounded-lg p-4">
              <p className="font-medium text-gray-900 mb-2">Local Currency:</p>
              <p className="text-lg font-bold text-[#B91C1C]">
                Bhutanese Ngultrum (BTN)
              </p>
              <p className="text-sm text-gray-600 mt-1">
                Pegged 1:1 with Indian Rupee (INR)
              </p>
            </div>
            <div className="space-y-2">
              <p className="font-medium text-gray-900">What's accepted:</p>
              <ul className="space-y-1">
                <li className="flex items-start gap-2">
                  <span className="text-green-600">✓</span>
                  <span>Indian Rupees (INR) widely accepted</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="text-green-600">✓</span>
                  <span>ATMs available in Paro and Thimphu</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="text-green-600">✓</span>
                  <span>Credit cards accepted at major hotels</span>
                </li>
              </ul>
            </div>
            <div className="bg-blue-50 border border-blue-200 rounded-lg p-3">
              <p className="text-sm text-gray-700">
                💡 <strong>Tip:</strong> Exchange money at the airport or banks for better rates
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Additional Tips */}
      <div className="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Other Arrival Tips
        </h3>
        <ul className="space-y-3 text-gray-700">
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Immigration process may take 30-60 minutes during peak times</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Keep your visa approval letter and hotel booking details handy</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Summit organizers will provide transfer details closer to the event</span>
          </li>
          <li className="flex items-start gap-3">
            <span className="text-[#B91C1C] mt-1">•</span>
            <span>Taxis and hotel shuttles available at the airport</span>
          </li>
        </ul>
      </div>
    </div>
  );
}

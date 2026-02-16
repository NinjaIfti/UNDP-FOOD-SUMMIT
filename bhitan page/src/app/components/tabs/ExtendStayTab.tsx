import { ExternalLink, CalendarPlus, DollarSign, AlertCircle } from 'lucide-react';

export function ExtendStayTab() {
  return (
    <div className="max-w-4xl mx-auto space-y-6">
      <h2 className="text-3xl font-bold text-gray-900">Extend Your Stay in Bhutan</h2>

      <div className="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-300 p-6">
        <p className="text-lg text-gray-800">
          Want to explore more of Bhutan? Extend your visa to experience the Land of the Thunder Dragon beyond the Summit.
        </p>
      </div>

      {/* Extension Process */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div className="flex items-start gap-4 mb-6">
          <div className="p-3 bg-amber-100 rounded-lg">
            <CalendarPlus className="w-6 h-6 text-amber-600" />
          </div>
          <div>
            <h3 className="text-xl font-semibold text-gray-900 mb-2">
              Extension Process
            </h3>
            <p className="text-gray-700">
              You can modify your visa dates online through the official portal.
            </p>
          </div>
        </div>

        <div className="space-y-4">
          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] font-bold mt-1">1.</span>
            <div>
              <p className="font-medium">Maximum stay: Up to 90 days total</p>
              <p className="text-sm text-gray-600">Subject to approval</p>
            </div>
          </div>

          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] font-bold mt-1">2.</span>
            <div>
              <p className="font-medium">Apply for extension before your current visa expires</p>
              <p className="text-sm text-gray-600">Processing takes 2-3 business days</p>
            </div>
          </div>

          <div className="flex items-start gap-3 text-gray-700">
            <span className="text-[#B91C1C] font-bold mt-1">3.</span>
            <div>
              <p className="font-medium">Must be arranged through a licensed tour operator</p>
              <p className="text-sm text-gray-600">Cannot extend independently</p>
            </div>
          </div>
        </div>
      </div>

      {/* Costs */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div className="flex items-start gap-4 mb-6">
          <div className="p-3 bg-green-100 rounded-lg">
            <DollarSign className="w-6 h-6 text-green-600" />
          </div>
          <div>
            <h3 className="text-xl font-semibold text-gray-900 mb-2">
              Extension Costs
            </h3>
          </div>
        </div>

        <div className="space-y-4">
          <div className="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p className="text-gray-900 mb-1">
              <strong className="text-xl">USD 100</strong> per night
            </p>
            <p className="text-sm text-gray-600">
              Sustainable Development Fee (SDF)
            </p>
          </div>

          <div className="space-y-2 text-gray-700">
            <p className="font-medium">Additional costs to consider:</p>
            <ul className="space-y-2 ml-4">
              <li className="flex items-start gap-2">
                <span className="text-[#B91C1C]">•</span>
                <span>Accommodation (varies by hotel)</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#B91C1C]">•</span>
                <span>Licensed tour guide (mandatory)</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#B91C1C]">•</span>
                <span>Transportation for sightseeing</span>
              </li>
              <li className="flex items-start gap-2">
                <span className="text-[#B91C1C]">•</span>
                <span>Meals outside hotel</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      {/* Important Requirements */}
      <div className="bg-red-50 border-2 border-red-300 rounded-xl p-6">
        <div className="flex gap-4">
          <AlertCircle className="w-6 h-6 text-red-600 flex-shrink-0 mt-1" />
          <div className="space-y-3">
            <h3 className="text-xl font-semibold text-gray-900">
              Important Requirement
            </h3>
            <div className="text-gray-700 space-y-2">
              <p>
                <strong>Licensed tourist guide required</strong> for all travel outside Paro/Thimphu during your extended stay.
              </p>
              <p className="text-sm">
                This is a Bhutan tourism regulation to ensure visitor safety and cultural preservation.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Portal Link */}
      <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Ready to Extend?
        </h3>
        <p className="text-gray-700 mb-4">
          Visit the official visa portal or contact your tour operator to arrange your extension.
        </p>
        <a
          href="https://www.tourism.gov.bt"
          target="_blank"
          rel="noopener noreferrer"
          className="inline-flex items-center gap-2 px-6 py-3 bg-[#B91C1C] text-white rounded-lg hover:bg-[#991B1B] transition-colors"
        >
          Official Visa Portal
          <ExternalLink className="w-4 h-4" />
        </a>
      </div>

      {/* Popular Extensions */}
      <div className="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6">
        <h3 className="text-xl font-semibold text-gray-900 mb-4">
          Popular Extension Activities
        </h3>
        <div className="grid md:grid-cols-2 gap-4">
          <div className="bg-white rounded-lg p-4">
            <h4 className="font-semibold text-gray-900 mb-2">Tiger's Nest Monastery</h4>
            <p className="text-sm text-gray-600">
              Iconic clifftop monastery hike (4-5 hours)
            </p>
          </div>
          <div className="bg-white rounded-lg p-4">
            <h4 className="font-semibold text-gray-900 mb-2">Thimphu Exploration</h4>
            <p className="text-sm text-gray-600">
              Capital city with dzongs and museums
            </p>
          </div>
          <div className="bg-white rounded-lg p-4">
            <h4 className="font-semibold text-gray-900 mb-2">Punakha Valley</h4>
            <p className="text-sm text-gray-600">
              Ancient capital with stunning fortress
            </p>
          </div>
          <div className="bg-white rounded-lg p-4">
            <h4 className="font-semibold text-gray-900 mb-2">Cultural Festivals</h4>
            <p className="text-sm text-gray-600">
              Traditional Tshechu celebrations
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
